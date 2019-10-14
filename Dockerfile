# Bring in from R docker image
FROM r-base:3.6.1

# Copy over our R libraries/runtime
COPY ./src /opt/executor

# Set working directory to our /opt/executor location
WORKDIR /opt/executor

# Install jsonlite R library
RUN R -e 'install.packages("jsonlite")'
