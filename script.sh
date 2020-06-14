#!/bin/bash
curl -X PUT https://dashboard.stryker-mutator.io/api/reports/github.com/Vection-Framework/Vection -H 'Content-Type: application/json' -H 'Host: https://dashboard.stryker-mutator.io' -H 'X-Api-Key: a81a5d12-3711-48dd-bd3d-0e2bc724aa8a' -d @mutation-report.json
