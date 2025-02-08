<template>
	<div v-if="$slots['header'] || $slots['default'] || $slots['footer']" class="card card-default">
        <div v-show="$slots['header']" class="card-header">
            <h3 class="font-weight-bold"><slot name="header"></slot></h3>
            <div class="card-tools">
                <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
                <button type="button" class="btn btn-tool" data-card-widget="remove"><i class="fas fa-remove"></i></button>
            </div>
        </div>

        <div v-show="$slots['default']" class="card-body">
			<slot></slot>
        </div>
		
    </div>
</template>