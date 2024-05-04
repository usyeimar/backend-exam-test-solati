<script setup lang="ts">
const closeSession = () => {
  localStorage.removeItem('token')
  window.location.href = '/'
}
</script>

<template>
  <div class="flex justify-center mt-[20px]  w-full mb-3">
    <button
      @click="closeSession"
      class="px-4 py-2 text-white bg-red-500 rounded-lg hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-opacity-50 w-full mt-4"
    >
      Cerrar Sesión
    </button>
  </div>
</template>

<style scoped>

</style>