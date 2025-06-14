# Use official Node.js LTS image
FROM node:18

# Create app directory
WORKDIR /usr/src/app/vendor_requests

# Copy package.json and package-lock.json from root context
COPY package.json package-lock.json* /usr/src/app/

# Install dependencies
WORKDIR /usr/src/app
RUN npm install --legacy-peer-deps

# Copy vendor_requests service files
COPY vendor_requests /usr/src/app/vendor_requests

# Expose port 4003
EXPOSE 4003

# Start the vendor_requests service
WORKDIR /usr/src/app/vendor_requests
CMD ["node", "index.js"]