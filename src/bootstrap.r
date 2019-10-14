# Include required libraries
library('jsonlite')

# Set system path variables
paths.data.json = '/opt/executor/data.json';
paths.config.json = '/opt/executor/config.json';
paths.output.json = '/opt/executor/output.json';
paths.script = '/opt/executor/script.r';

# Read in data and config
data = read_json(paths.data.json, TRUE, TRUE, TRUE, TRUE)
config = read_json(paths.config.json, TRUE, TRUE, TRUE, TRUE)

# The default output
output = NULL

# The script should assign something to output
source(paths.script)

# Execute script and write output
write_json(
    output,
    paths.output.json, auto_unbox = TRUE
)