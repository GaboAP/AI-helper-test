<h1 align="center"> Topic Idea Generator </h1> 

<p align="center">Small prototype that helps a journalist generate content ideas from a topic.</p>

## What it does

Type a topic (e.g., “local election”, “sports news”) and the app returns 3–5 concise, clear, and unique ideas with short descriptions to jump-start a piece or blog post.

Tech stack: Laravel 12 (API) + React 19/TypeScript (Vite).
Monorepo layout:
AI-helper-test/
├─ backend/   # Laravel API
└─ frontend/  # React + Vite UI

## Prerequisites

1. Node.js (v18+ recommended) & npm

2. PHP 8.2+

3. Composer

## Installation

### For Laravel 12, backend (API):

1. Once the repo is cloned, execute `composer install` inside the backend folder
2. Copy the .env.example and add the OpenAI API key (if not, stubs will be in place), change the frontend address if its going to be different (right now its set with the default one)
3. execute `php artisan key:generate`
4. Run the app with `php artisan serve`
5. API Endpoint is /api/suggestions
6. Expected JSON body looks like this: { "prompt": "local election", "model": "gpt-4o-mini" }

### For React 19, frontend

1. Once in the cloned repo, execute `npm install` inside the frontend folder
2. Copy .env.example and rename to .env
3. Change base URL in .env if the laravel application is running on a different port/address for some reason
4. Run! `npm run dev`

## Project Status

This is a minimal prototype focused on structure and clarity:

- Clean API boundary (POST /api/suggestions)

- Typed frontend client, simple error states

- Works with real OpenAI or static fallbacks

