<script setup lang="ts">
defineProps({
  pendingTasks: Number
})
</script>
<template>
  <div class="flex justify-between mt-[0] mx-[0] mb-[20px]">
    <span>Tareas Pendientes: {{ pendingTasks }} </span>
  </div>
</template>

<style scoped>

</style>