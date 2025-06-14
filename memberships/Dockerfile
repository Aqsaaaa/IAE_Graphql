# Use official Node.js LTS image
FROM node:18

# Create app directory
WORKDIR /usr/src/app/memberships

# Copy package.json and package-lock.json from root context
COPY package.json package-lock.json* /usr/src/app/

# Install dependencies
WORKDIR /usr/src/app
RUN npm install --legacy-peer-deps

# Copy memberships service files
COPY memberships /usr/src/app/memberships

# Expose port 4002
EXPOSE 4002

# Start the memberships service
WORKDIR /usr/src/app/memberships
CMD ["node", "index.js"]
