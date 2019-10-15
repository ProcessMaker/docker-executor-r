# docker-executor-r
Script Task Executor Engine with R Runtime

This docker image provides a sandboxed protected environment to run custom R scripts that are written in ProcessMaker 4.
User created script tasks should be isolated however have utilities available to them in order to get most common tasks done. This 
R environment has R packages available and autoloaded so Script Tasks can take advantage of the following libraries:

- jsonlite

## How to use
The execution requires a data.json, config.json and an output.json file be present on the host system. The data.json represents the 
Request instance data.  The config.json represents configuration specific for this Script Task. And the output.json should be a blank 
file that will be populated by the successful output of the script task. The script task is represented by a script.r file.
It is the responsibility of the caller to have these files prepared before executing the engine via command line (or docker API).

## Script Task design
When writing a Script Task, three variables are available.  They are:

- data - An R Object returned by jsonlite that represents the current data of the rqeuest
- config - An R Object represents the config loaded from config.json
- output - Assign your output to this variable which will be serialized to json and saved as the output of the script.

Your script should execute quickly. Once the script is complete, the output variable will converted to JSON which
will be stored in the output.json file.  Once the docker execution is complete, you should use the return code of the docker execution. 
If the code is 0, then the script task executed successfully and you can read output.json for the valid output.  If it is non-zero,
then you should review STDERR to see the error that was displayed during execution.

### Example data.json
```json
{
  "firstname": "Taylor"
}
```

### Example Script Task
```r
output <- data
output$first_name <- toupper(output$first_name)
```

### Example output.json
```json
{"firstname":"TAYLOR"}
```

## Command Line Usage
```bash
$ docker run -v <path to local data.json>:/opt/executor/data.json \
  -v <path to local config.json>:/opt/executor/config.json \
  -v <path to local script.r>:/opt/executor/script.r \
  -v <path to local output.json>:/opt/executor/output.json \
  processmaker/executor:r \
  Rscript /opt/executor/bootstrap.r
```
