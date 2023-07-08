#!/bin/bash

while true; do bin/console messenger:consume async --limit=10 --time-limit=3600; done
