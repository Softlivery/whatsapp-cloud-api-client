# WhatsApp Cloud API Client - Integration Fix Plan

Date: 2026-02-26
Owner: Library team
Related projects: `contact-center-platform`, `contact-center-chat`, `softwork.php`

## 1. Objective
Provide a stable, explicit integration contract for OAuth code exchange and WhatsApp onboarding operations used by the platform adapter.

## 2. Issues Owned by This Project

### A. OAuth helper contract needs stronger guarantees
Problem:
- Platform depends on `exchangeCode(..., redirectUri)` but currently passes empty redirect URI in adapter.
- Library should make redirect URI expectations explicit and test-backed.
- Compatibility decision: empty `redirect_uri` remains allowed, but must be documented.

Impact:
- Hidden breakage risk during embedded signup flows.

### B. Legacy class naming requires a breaking cleanup
Problem:
- Class `oAuthHelper` is non-standard naming and encourages inconsistent usage.

Impact:
- Naming inconsistency leaks into downstream code and docs.
- Cleanup requires a major release because the old class is removed.

### C. Release/version workflow mismatch with platform consumption
Problem:
- Platform consumes pinned commit from feature branch while local repo may advance independently.

Impact:
- Integration confidence drops because local code and runtime vendor code diverge.

## 3. Shared Cross-Project Dependencies

### Required coordination with `contact-center-platform`
- Platform must pass actual redirect URI during code exchange.
- Platform should adopt stable tags/branches after library changes are finalized.

### Required coordination with `contact-center-chat`
- No direct runtime dependency, but embedded signup payload assumptions in UI affect backend call shape.

### Required coordination with `softwork.php`
- None required for runtime behavior; only release/documentation alignment.

## 4. Recommended Implementation Steps

1. Lock down OAuth exchange behavior with tests
- Add/expand tests covering `RequestFactory::exchangeCode`:
  - includes `client_id`, `client_secret`, `code`, `redirect_uri`.
  - rejects or clearly documents empty redirect URI behavior.

2. Execute naming cleanup for v1
- Introduce canonical class name `OAuthHelper`.
- Remove `oAuthHelper` in v1 and document migration.
- Remove deprecated `CodeExchangeApiRequest`; use `RequestFactory::exchangeCode`.

3. Improve docs for platform integrators
- Add explicit section: embedded signup code exchange requires matching redirect URI.
- Add practical examples with strict redirect URI configured.

4. Improve release discipline
- Publish stable tag for integration-ready version.
- Provide upgrade notes consumed by `contact-center-platform`.

## 5. Acceptance Criteria
- OAuth exchange request construction is test-covered and deterministic.
- Platform migrates to canonical helper naming (`OAuthHelper`).
- Integration documentation clearly states redirect URI requirements and failure modes.
- A tagged version is available for backend consumption.

## 6. Current Status (2026-02-28)
- `RequestFactory::exchangeCode` is test-covered for both non-empty and empty `redirect_uri`.
- Canonical helper is `OAuthHelper`; legacy `oAuthHelper` was removed for v1 cleanup.
- Deprecated `CodeExchangeApiRequest` was removed; factory path is authoritative.
- Documentation explicitly states that empty `redirect_uri` is allowed for compatibility, but exact redirect URI is recommended.

## 7. Close-Context Input Package (for implementation agent)

### Files to provide first
- `src/Request/RequestFactory.php`
- `src/OAuthHelper.php`
- `README.md`
- `tests/*` relevant to OAuth helper and request factory

### Required external contract notes
- Backend adapter call site and expected redirect URI source.
- Target version/tag expected by platform.

### Execution order
1. Tests for exchange-code contract.
2. Canonical helper naming cleanup.
3. Docs update.
4. Tag/release handoff.

### Verification commands
- `composer test`
