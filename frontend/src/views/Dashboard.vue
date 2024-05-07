<script setup lang="ts">

import LogoutButton from '@/components/LogoutButton.vue'
import {defineAsyncComponent} from "vue";

const AsyncTask = defineAsyncComponent(() => import('@/components/Task.vue'))

</script>

<template>
  <div class="max-w-[700px]  mx-auto my-0 px-[15px] py-0  pt-4">
    <LogoutButton/>
    <AsyncTask/>
  </div>
</template>

<style scoped>

</style>