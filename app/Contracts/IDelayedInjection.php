<?php

namespace TeaPageContent\App\Contracts;

interface IDelayedInjection {
    public function set_dependency($depedency);
    public function resolve_dependency_name($depedency);
}