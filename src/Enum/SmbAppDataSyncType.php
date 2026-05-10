<?php declare(strict_types=1);

namespace Softlivery\WhatsappCloudApiClient\Enum;

/**
 * Sync types accepted by `POST /{phoneNumberId}/smb_app_data` for the
 * Coexistence onboarding flow. Each value triggers exactly one sync per
 * customer; a successful call cannot be retried without re-onboarding.
 */
final class SmbAppDataSyncType
{
    public const SMB_APP_STATE_SYNC = 'smb_app_state_sync';
    public const HISTORY = 'history';

    private function __construct() {}
}
