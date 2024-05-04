<script setup lang="ts">
defineProps<{
  title: string
}>()
</script>

<template>
  <div class="text-center mt-[0] mx-[0] mb-[20px]">
    <h1 class="text-2xl text-gray-900 dark:text-white">
      {{ title }}
    </h1>
  </div>
</template>

<style scoped>

</style>