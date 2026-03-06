# AGENTS.md - whatsapp-cloud-api-client

## Purpose
This repository is the WhatsApp Cloud API client library used by `contact-center-platform`. It owns request building, response mapping, webhook parsing helpers, and client abstractions.

## Scope Boundaries
- Owns: Graph API request/response contract in library code, SDK-level helper behavior, library tests.
- Does not own: platform domain logic, UI flows, framework lifecycle behavior.

## Setup / Run / Test
- Install: `composer install`
- Tests: `composer test`

## Required Integration Contracts
- Integration plan: `docs/INTEGRATION_FIX_PLAN.md`
- Primary consumer: `../contact-center-platform`

## Library Contract Discipline
- Changes to request builders (especially OAuth/code-exchange/webhook parsing) must include tests.
- Maintain backwards compatibility for public classes/methods; if a rename is needed, provide compatibility alias first.
- Clearly document required parameters (for example `redirect_uri` behavior in code exchange).

## Quality Gate Policy
Before merge, run:
1. `composer test`

## Cross-Project Rules
- Coordinate any OAuth helper behavior changes with `../contact-center-platform` adapter code.
- Provide upgrade notes/tag guidance for backend consumers.
- Keep README examples consistent with current API methods.

## Do / Do Not
- Do keep DTO and response mapping deterministic and test-backed.
- Do fail predictably on malformed payload/signature validation.
- Do not introduce breaking public API changes without compatibility path.
- Do not assume platform-specific business behavior inside the library.
