<script setup lang="ts">

import { FwbButton } from 'flowbite-vue'
import router from '@/router'
</script>

<template>
  <div
    class="flex flex-col bg-slate-100 items-center justify-center px-6 pt-8 mx-auto md:h-screen pt:mt-0 dark:bg-gray-900">
    <div class="w-full max-w-md">
      <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg p-6">
        <h2 class="text-2xl font-semibold text-center text-gray-800 dark:text-gray-200">Verifica tu correo</h2>
        <p class="text-gray-600 dark:text-gray-400 text-center mt-2">
          Gracias por registrarte.
          Hemos enviado un email a tu cuenta de correo
          electronico, por favor verifica tu correo para continuar</p>
        <div class="mt-8 flex flex-col gap-y-4">

          <button @click="router.push('/login')"
                  class="py-4 bg-green-700 rounded-xl text-white font-semibold hover:bg-green-600">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 inline-block mr-2" fill="none" viewBox="0 0 24 24"
                 stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M9 5l7 7-7 7" />
            </svg
            >
            Iniciar Sesion
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<style scoped>

</style>