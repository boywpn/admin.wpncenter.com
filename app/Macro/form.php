<?php


Html::component('section', 'core::components.section', ['language_file','panelName','sectionButtons']);

Html::component('renderField', 'core::components.renderField', ['entity','fieldName','options','language_file']);

Html::component('customButton', 'core::components.customButton', ['btn']);
