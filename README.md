# AVflow
A converting, archive and streaming server, build in a docker container.

[![Docker Image CI](https://github.com/andreaskasper/AVflow/actions/workflows/docker-ci.yml/badge.svg)](https://github.com/andreaskasper/AVflow/actions/workflows/docker-ci.yml)

### Build status:
[![Docker Image CI](https://github.com/andreaskasper/AVflow/actions/workflows/docker-ci.yml/badge.svg)](https://github.com/andreaskasper/AVflow/actions/workflows/docker-ci.yml)<br/>
Web: ![Build Status](https://img.shields.io/docker/image-size/andreaskasper/avflow/web)<br/>
CLI: ![Build Status](https://img.shields.io/docker/image-size/andreaskasper/avflow/cli)

### Bugs & Issues:
![Github Issues](https://img.shields.io/github/issues/andreaskasper/AVflow.svg)

### Stats:
![Activities](https://img.shields.io/github/commit-activity/m/andreaskasper/AVflow.svg)
![Last Commit](https://img.shields.io/github/last-commit/andreaskasper/AVflow.svg)
![Code Languages](https://img.shields.io/github/languages/top/andreaskasper/AVflow.svg)
![Docker Pulls](https://img.shields.io/docker/pulls/andreaskasper/avflow.svg)

### Running the container :

#### Simple Run

```sh
$ docker run -d -h example.com -p 8080:80  andreaskasper/avflow:latest
```

### Environment Parameters
| Parameter     | Description                                             | Example                     |
| ------------- |:-------------------------------------------------------:|:---------------------------:|
| USER_NAME     | Username to login to the fileexplorer                   |                             |
| USER_PASSWORD | Password to login to the fileexplorer                   |                             |
| CONVERTS      | Converting formats                                      | 1080p.mp4,480p.mp4,240p.mp4 |



### Folders:
| Folder        | Description                       |
| ------------- |:---------------------------------:|
| /in           | Where all the incomming files are |
| /out          | Where all the converted files are |
| /data         | All data files including config   |



### Steps
- [x] Build a base test image to test this build process (Travis/Docker)
- [ ] Build tests
- [ ] Gnomes
- [ ] Profit

### Links
[🐋 Docker Hub]([https://hub.docker.com/r/andreaskasper/avflow](https://hub.docker.com/r/andreaskasper/avflow))

### support the projects :hammer_and_wrench:
[![donate via Patreon](https://img.shields.io/badge/Donate-Patreon-green.svg)](https://www.patreon.com/AndreasKasper)
[![donate via PayPal](https://img.shields.io/badge/Donate-PayPal-green.svg)](https://www.paypal.me/AndreasKasper)
