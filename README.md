# elsevier-io/json-schema-php-generator [![Build Status](https://travis-ci.org/elsevier-io/json-schema-php-generator.svg?branch=master)](https://travis-ci.org/elsevier-io/json-schema-php-generator.svg?branch=master)


Tool to generate PHP representations of the data structures in a JSON Schema

There is a command-line script to run the tool:
```bash
./bin/php-json-schema-generate
```
Running it without any args or options will give you usage docs.

So far this is only a partial implementation of the JSON Schema. See tests for which aspects have been implemented.

## TODOs
- Add console logging to command
- Add option to read configs from file rather than pass in as args
- Make namespace argument optional (and hence create output without any namespaces)
