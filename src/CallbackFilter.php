<?php

declare(strict_types=1);

namespace SimpleStreamFilter;

final class CallbackFilter extends \php_user_filter
{
    public function filter($in, $out, &$consumed, $closing): int
    {
        $bucket = \stream_bucket_make_writeable($in);

        if ($bucket !== null) {
            $consumed += $bucket->datalen;

            try {
                if ($bucket->data !== null) {
                    $bucket->data = ($this->params)($bucket->data);

                    \stream_bucket_append($out, $bucket);
                }
            } catch (\Exception $e) {
                \trigger_error('Error invoking filter.', \E_USER_WARNING);

                return \PSFS_ERR_FATAL;
            }
        }

        return \PSFS_PASS_ON;
    }
}
