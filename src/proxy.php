<?PHP
    $postdata = file_get_contents ("php://input");
    $obj = json_decode ($postdata, true);

    $checkToken = getenv("PASSED_API_TOKEN");
    $apiToken = getenv("PROXY_API_TOKEN");
    $vcs = getenv("PROXY_VCS");
    $org = getenv("PROXY_ORG");
    $repo = getenv("PROXY_REPO");
    $branch = getenv("PROXY_BRANCH");

    file_put_contents ("./json_requests.txt", json_encode ($obj), FILE_APPEND);

    if ($_SERVER['PHP_AUTH_USER'] != $checkToken)
    {
        header ('HTTP/1.0 401 Unauthorized');
        echo "unauthorized";

        file_put_contents ("./unauthorized.txt", json_encode (array (
                "user" => $_SERVER['PHP_AUTH_USER'],
                "password" => $_SERVER['PHP_AUTH_PW']
            )), FILE_APPEND);

        exit;
    }

    // For CircleCI APIv2 when its ready
    //$url = "https://circleci.com/api/v2/project/{$vcs}/{$org}/{$repo}/pipeline";

    // CircleCI APIv1.1
    $url = "https://circleci.com/api/v1.1/project/{$vcs}/{$org}/{$repo}/tree/{$branch}";
    $finalObjToSend = null;

    if ($obj["buildStatus"] == "success")
    {
        $buildShareLink = $obj["links"]["artifacts"][0]["files"][0]["href"];

        /*
        // For CircleCI APIv2
        $objToSend = array (
                "branch" => $branch,
                "parameters" => array (
                        "UNITY_SHARE_LINK" => $buildShareLink
                    )
            );*/

        $objToSend = array (
                "build_parameters" => array (
                        "UNITY_SHARE_LINK" => $buildShareLink
                    )
            );

        $finalObjToSend = json_encode ($objToSend);
    }
    else
    {
        $finalObjToSend = "build_parameters[CIRCLE_JOB]=build-failed";
    }

    $ch = curl_init ($url);

    curl_setopt ($ch, CURLOPT_HTTPHEADER, array(
                //"authorization: Basic {$apiToken}", // For CircleCI APIv2.
                "Circle-Token: {$apiToken}",
                "Content-Type: application/json"
            )); 
    curl_setopt ($ch, CURLOPT_POST, 1);
    curl_setopt ($ch, CURLOPT_POSTFIELDS, $finalObjToSend);

    $result = curl_exec ($ch);
    curl_close ($ch);

    echo "{$result}";
?>