# Use the node image from official Docker Hub
FROM node:20-slim AS base

ENV PNPM_HOME="/pnpm"
ENV PATH="$PNPM_HOME:$PATH"
RUN corepack enable

COPY . /var/www/html
WORKDIR /var/www/html




FROM base AS build
RUN --mount=type=cache,id=pnpm,target=/pnpm/store pnpm install --frozen-lockfile



# Expose the port 8082
EXPOSE 8082
# Start Nginx to serve the application
CMD ["pnpm", "dev"]