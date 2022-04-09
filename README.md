# Unity Cloud Builder Proxy
Since the Unity Cloud DevOps solution currently sucks (especially their webhooks), we need a proxy to tell CircleCI to build the game image, then push that image to the image repository.

## Getting Started
Build the image, then push it to the image repository:
```console
./build.sh
```
```console
./push.sh
```
After the image has been pushed to the repository, login to Rancher, and update the app deployment called Unity Builder Proxy.

## Testing
First copy `env-skeleton` to `.env` and enter all the required information inside it. Then start the container by doing:
```console
./start.sh
```

Execute the test by doing:
```console
./tests/post_call.sh
```