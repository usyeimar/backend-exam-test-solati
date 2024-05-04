<script setup lang="ts">
import { defineProps } from 'vue'
import { Task } from '@/types'

</script>

<template>
  <div class="max-w-[700px]  mx-auto my-0 px-[15px] py-0  pt-4">

    <div class="bg-[#fff] rounded-md p-[30px] shadow flex-col">
      <!-- Task Title -->
      <h1 class="text-5xl text-gray-900 dark:text-white">Título de la tarea</h1>

      <!-- Task Description -->
      <div class="flex justify-between mt-[20px] mx-[0] mb-[20px]">
        <p class="text-lg text-gray-900 dark:text-white">Descripción de la tarea</p>
      </div>

      <div class="flex justify-between mt-[20px] mx-[0] mb-[20px] flex-col">
        <h3>
          Archivos adjuntos
        </h3>
        <ul>
          <li>
            <a href="#">Archivo 1</a>
          </li>
          <li>
            <a href="#">Archivo 2</a>
          </li>
        </ul>

      </div>


      <!-- Task Details -->
      <div class="flex justify-between mt-[20px] mx-[0] mb-[20px] flex-col">
        <p class="text-sm text-gray-900 dark:text-white">
          <span class="font-semibold">Asignada a: </span>
          <span>Usuario</span>
        </p>
        <p class="text-sm text-gray-900 dark:text-white">
          <span class="font-semibold">Fecha de vencimiento: </span>
          <span>2021-10-10</span>
        </p>

        <p class="text-sm text-gray-900 dark:text-white">
          <span class="font-semibold">Estado de la tarea: </span>
          <span class="text-green-500">Completada</span>
        </p>
        <p class="text-sm text-gray-900 dark:text-white">
          <span class="font-semibold">Fecha de creación: </span>
          <span>2021-10-10</span>
        </p>


      </div>

    </div>
  </div>
</template>

<style scoped>

</style>