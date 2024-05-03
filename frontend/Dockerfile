# Use the node image from official Docker Hub
FROM node:current-alpine


# set the working directory
WORKDIR /var/www/html

COPY . /var/www/html
#
RUN yarn install -y


# Expose the port 8082
EXPOSE 8082
# Start Nginx to serve the application
CMD ["yarn","dev"]   