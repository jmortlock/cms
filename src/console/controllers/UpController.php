<?php
/**
 * @link https://craftcms.com/
 * @copyright Copyright (c) Pixel & Tonic, Inc.
 * @license https://craftcms.github.io/license/
 */

namespace craft\console\controllers;

use Craft;
use craft\console\Controller;
use craft\errors\OperationAbortedException;
use craft\helpers\Console;
use yii\console\Exception;
use yii\console\ExitCode;

/**
 * Runs pending migrations and applies pending project config changes.
 *
 * @author Pixel & Tonic, Inc. <support@pixelandtonic.com>
 * @since 3.7.13
 */
class UpController extends Controller
{
    /**
     * @var bool Whether to perform the action even if a mutex lock could not be acquired.
     */
    public $force = false;

    /**
     * @inheritdoc
     */
    public function options($actionID)
    {
        return array_merge(parent::options($actionID), [
            'force',
        ]);
    }

    /**
     * Runs pending migrations and applies pending project config changes.
     * @return int
     */
    public function actionIndex(): int
    {
        $lockName = 'craft-up';
        $mutex = Craft::$app->getMutex();
        $this->stdout('🔒 Acquiring lock ... ');
        if (!$mutex->acquire($lockName) && !$this->force) {
            $this->stderr("Couldn’t acquire a mutex lock. Run again with --force to bypass.\n", Console::FG_RED);
            return ExitCode::UNAVAILABLE;
        }
        $this->stdout("done\n\n", Console::FG_GREEN);

        try {
            if ($this->module->runAction('migrate/all') !== ExitCode::OK) {
                $this->stderr("\nAborting remaining tasks.\n", Console::FG_RED);
                throw new OperationAbortedException();
            }
            $this->stdout("\n");
            if ($this->module->runAction('project-config/apply') !== ExitCode::OK) {
                throw new OperationAbortedException();
            }
            $this->stdout("\n");
        } catch (\Throwable $e) {
            if (!$e instanceof OperationAbortedException) {
                throw $e;
            }
            return ExitCode::UNSPECIFIED_ERROR;
        } finally {
            $this->stdout("🔓 Releasing lock ... ");
            if ($mutex->release($lockName)) {
                $this->stdout("done\n", Console::FG_GREEN);
            } else {
                $this->stderr("Couldn’t release lock.\n");
            }
        }

        return ExitCode::OK;
    }
}
