<?php
namespace Lanix;
use Symfony\Component\Process\Process;
class PersistentProcess extends Process
{
    //This process is not supposed to be stopped
    public function __destruct() {}

}
