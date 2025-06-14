# Use official Node.js LTS image
FROM node:18

# Create app directory
WORKDIR /usr/src/app/deliveries

# Copy package.json and package-lock.json from root context
COPY package.json package-lock.json* /usr/src/app/

# Install dependencies
WORKDIR /usr/src/app
RUN npm install --legacy-peer-deps

# Copy deliveries service files
COPY deliveries /usr/src/app/deliveries

# Expose port 4001
EXPOSE 4001

# Start the deliveries service
WORKDIR /usr/src/app/deliveries
CMD ["node", "index.js"]
