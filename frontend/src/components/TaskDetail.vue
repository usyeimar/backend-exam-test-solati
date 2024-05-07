<script setup lang="ts">
import type { Task } from '@/types'
import { onMounted, ref } from 'vue'
import { Api } from '@/api/api'

import { TrashIcon, CloudArrowDownIcon, BackspaceIcon } from '@heroicons/vue/24/solid'

const api = new Api()
const loading = ref(false)
const uploading = ref(false)
const task = ref<Task | null>(null)
const props = defineProps<{
  id: string
}>()

const deleteAttachment = async (id: string) => {
  await api.deleteAttachment(id)
    .then(() => {
      console.log('Deleted')
    })
    .catch(() => {
      console.log('Error deleting')
    })
}

const downloadAttachment = async (id: string, name: string) => {
  await api.downloadAttachment(id, name)
    .then(() => {
      console.log('Downloaded')
    })
    .catch(() => {
      console.log('Error downloading')
    })
}

const uploadAttachment = async () => {
  uploading.value = true
  const fileInput = document.getElementById('upload-file') as HTMLInputElement
  fileInput.click()
  fileInput.onchange = async () => {
    const file = fileInput.files?.[0]
    if (file) {
      await api.uploadAttachment(props.id, file)
        .then(async () => {
          uploading.value = false
          //Refresh task details
          await api.getTask(props.id)
            .then(({ data }) => {
              task.value = data
            })
        })
        .catch(() => {
          console.log('Error uploading')
        })
    }
  }
}


onMounted(async () => {
  // Fetch task details
  loading.value = true
  await api.getTask(props.id)
    .then(({ data }) => {
      task.value = data
      loading.value = false
    })
})


</script>

<template>
  <div class="max-w-[700px]  mx-auto my-0 px-[15px] py-0  pt-4">

    <div class="bg-[#fff] rounded-md p-[30px] shadow flex-col" v-if="!loading">
      <button class="bg-emerald-500 hover:bg-emerald-700 text-white  py-2 px-4 rounded mb-3" @click="$router.back()">
        Regresar
      </button>
      <!-- Task Title -->
      <h1 class="text-5xl text-gray-900 dark:text-white">
        {{ task?.title }}
      </h1>

      <!-- Task Description -->
      <div class="flex justify-between mt-[20px] mx-[0] mb-[20px]">
        <p class="text-lg text-gray-900 dark:text-white">
          {{ task?.description }}
        </p>
      </div>

      <div class="flex justify-between mt-[20px] mx-[0] mb-[20px] flex-col" >
        <input type="file" class="hidden" id="upload-file" @change="uploadAttachment" />

        <div class="flex justify-between mt-[20px] mx-[0] mb-[20px]">
          <h2 class="text-2xl text-gray-900 dark:text-white">
            Media(assets)
          </h2>
          <button class="bg-emerald-500 hover:bg-emerald-700 text-white  py-2 px-4 rounded flex items-center"
                  @click="uploadAttachment">
            <svg v-if="uploading" class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg"
                 fill="none" viewBox="0 0 24 24">
              <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
              <path class="opacity-75" fill="currentColor"
                    d="M4 12a8 8 0 018-8V4a10 10 0 00-10 10h2zm2 8a8 8 0 018-8h2a10 10 0 00-10-10v2zm8 0a8 8 0 01-8 8v2a10 10 0 0010-10h-2zm-8 0a8 8 0 018-8h2a10 10 0 00-10 10v-2z"></path>
            </svg>
            <span v-if="uploading">Subiendo archivo...</span>
            <span v-else>Subir archivo</span>
          </button>
        </div>

        <p class="text-sm text-gray-900 dark:text-white" v-if="task?.attachments.length">
          ({{ task?.attachments.length }}) Recursos adjuntos
        </p>
        <p class="text-sm text-gray-900 dark:text-white" v-else>
          No hay recursos adjuntos
        </p>

        <ul class="flex flex-col mt-[20px] mx-[0] mb-[20px] bg-gray-100 rounded p-4 justify-between" v-if="task?.attachments.length">
          <li v-for="attachment in task?.attachments" :key="attachment.id"
              class="text-sm shadow bg-white p-4  rounded text-gray-900 dark:text-white flex mb-1">
            <div>
              <div class="flex items-center gap-2">
                {{ attachment.display_name }}
              </div>
              <div class="flex items-center gap-2">
                <TrashIcon class="w-4 h-4 text-red-500 cursor-pointer"
                           @click="deleteAttachment(attachment.uuid)" />
                <CloudArrowDownIcon class="w-4 h-4 text-green-500 cursor-pointer"
                                    @click="downloadAttachment(attachment.uuid,attachment.display_name)" />


              </div>
            </div>
          </li>
        </ul>
      </div>


      <!-- Task Details -->
      <div class="flex justify-between mt-[20px] mx-[0] mb-[20px] flex-col">
        <p class="text-sm text-gray-900 dark:text-white">
          <span class="font-semibold">Asignada a: </span>
          <span>
            {{ task?.user.name }}
          </span>
        </p>
        <p class="text-sm text-gray-900 dark:text-white">
          <span class="font-semibold">Fecha de vencimiento: </span>
          <span>
            {{ task?.due_at }}
          </span>
        </p>

        <p class="text-sm text-gray-900 dark:text-white">
          <span class="font-semibold">Estado de la tarea: </span>
          <span class="text-green-500"
                :class="{
            'text-red-500': !task?.completed
          }"
          >
            {{ task?.completed ? 'Completada' : 'Pendiente' }}
          </span>
        </p>
        <p class="text-sm text-gray-900 dark:text-white">
          <span class="font-semibold">Fecha de creación: </span>
          <span>
            {{ task?.created_at }}
          </span>
        </p>


      </div>

    </div>

    <div v-if="loading" class="bg-[#fff] rounded-md p-[30px] shadow flex justify-center">
      <p class="text-2xl text-gray-900 dark:text-white">Cargando...</p>
    </div>

  </div>
</template>

<style scoped>

</style>