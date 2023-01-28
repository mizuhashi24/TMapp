<script setup>
</script>

<template>
    <div v-if="$page.props.flash.message" class="flex items-center font-medium text-emerald-500">
        <p class="block">{{ $page.props.flash.message }}</p>
    </div>
</template> 
