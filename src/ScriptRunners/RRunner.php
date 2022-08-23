<?php

namespace ProcessMaker\ScriptRunners;

class RRunner extends Base
{
    /**
     * Configure docker with R executor
     *
     * @param string $code
     * @param array $dockerConfig
     *
     * @return array
     */
    public function config($code, array $dockerConfig)
    {
        $dockerConfig['image'] = config('script-runners.r.image');
        $dockerConfig['command'] = 'Rscript /opt/executor/bootstrap.r';
        $dockerConfig['inputs']['/opt/executor/script.r'] = $code;

        return $dockerConfig;
    }
}
