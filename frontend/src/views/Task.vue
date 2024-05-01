<script setup lang="ts">
import { computed, onMounted, ref } from 'vue'
import { TrashIcon, PlusIcon } from '@heroicons/vue/24/solid'
import { Api } from '@/api/api'
import { toast } from 'vue3-toastify'
import router from '@/router'
import { formatearFecha } from '@/helper'

const isLoadingTasks = ref(false)
const api = new Api()
const newTask = ref('')

const tasks = ref([])

const pendingTasks = computed(() => {
  return tasks.value.filter((task: { completed: boolean }) => !task.completed).length
})


const addTask = () => {
  const newTask = document.getElementById('new_task') as HTMLInputElement
  if (newTask.value) {
    const task = {
      title: newTask.value,
      description: 'no description',
      due_at: formatearFecha(new Date())
    }
    newTask.value = ''
    api.createTask(task).then(() => {
      toast('Task created', { type: 'success', delay: 100, autoClose: true })
      tasks.value.push(task)
    }).catch((error) => {
      console.log('Error', error)
    })
  }
}

const closeSession = () => {
  localStorage.removeItem('token')
  window.location.href = '/'
}

const deleteTask = async (id: string) => {
  const index = tasks.value.findIndex((task: { uuid: string }) => task.uuid === id)
  tasks.value.splice(index, 1)

  await api.deleteTask(id).then(() => {
    toast('Task deleted', { type: 'success', delay: 100, autoClose: true })
  }).catch((error) => {
    console.log('Error', error)
  })

}


onMounted(async () => {
  isLoadingTasks.value = true
  const { data } = await api.getAllTasks().then(() => {
    isLoadingTasks.value = false
  }).catch((error) => {
    console.log('Error', error)
  })
  tasks.value.push(...data)
})

</script>
<template>
  <div class="max-w-[700px]  mx-auto my-0 px-[15px] py-0  pt-4">
    <div class="bg-[#fff] rounded-[25px] p-[30px] [box-shadow:0px_0px_40px_0px_rgba(0,0,0,0.1)]">
      <!-- title -->
      <div class="text-center mt-[0] mx-[0] mb-[20px]">
        <h1>
          Lista de Tareas
        </h1>
      </div>
      <!-- form -->
      <div class="relative mt-[0] mx-[0] mb-[30px]">
        <input type="text" id="new_task"
               v-model="newTask"
               @keyup.enter="addTask"
               class="w-full h-[50px] p-[15px] bg-[#f3f3f3] text-[#333] border-[1px] border-[transparent] rounded-[10px] [transition:border_0.3s_linear]"
               placeholder="Nueva tarea"
               required />
        <PlusIcon
          class="h-[20px] w-[20px] mb-2 text-gray-400 cursor-pointer absolute top-[50%] right-[20px] transform [-translate-y-1/2]"
          @click="addTask" />
      </div>

      <!-- task lists -->
      <div class="px-[10px] py-[0]">
        <ul v-if="tasks.length > 0">
          <li v-for="task in tasks"
              :key="task.uuid"
              class="flex justify-between mt-[0] mx-[0] mb-[20px]">
            <button>
              {{ task.title }}
            </button>
            <TrashIcon class="h-[20px] w-[20px] text-gray-400 cursor-pointer"
                       @click="deleteTask(task.uuid)"
            />
          </li>
        </ul>

        <div v-if="tasks.length == 0 && !isLoadingTasks" class="text-center mt-[0] mx-[0] mb-[20px]">


          <div
            class="w-full p-4 text-center bg-white border border-gray-200 rounded-lg shadow sm:p-8 dark:bg-gray-800 dark:border-gray-700">
            <h5 class="mb-2 text-3xl font-bold text-gray-900 dark:text-white">
              No tienes tareas pendientes
            </h5>
            <p class="mb-5 text-base text-gray-500 sm:text-lg dark:text-gray-400">
              Recuerda que para llevar un control muchos mas eficiente de tus tareas, es importante que las marques como
              completadas una vez que las hayas terminado.
            </p>

          </div>

        </div>

        <div v-if="isLoadingTasks" class="text-center mt-[0] mx-[0] mb-[20px]">

          <div
            class="w-full p-4 text-center bg-white border border-gray-200 rounded-lg shadow sm:p-8 dark:bg-gray-800 dark:border-gray-700">
            <h5 class="mb-2 text-3xl font-bold text-gray-900 dark:text-white">
              Cargando tareas
            </h5>
            <p class="mb-5 text-base text-gray-500 sm:text-lg dark:text-gray-400">
              Por favor espera un momento, estamos cargando tus tareas.
            </p>
          </div>
          <div role="status" class="absolute -translate-x-1/2 -translate-y-1/2  left-1/2">
            <svg aria-hidden="true" class="w-8 h-8  text-gray-200 animate-spin dark:text-gray-600 fill-blue-600"
                 viewBox="0 0 100 101" fill="none" xmlns="http://www.w3.org/2000/svg">
              <path
                d="M100 50.5908C100 78.2051 77.6142 100.591 50 100.591C22.3858 100.591 0 78.2051 0 50.5908C0 22.9766 22.3858 0.59082 50 0.59082C77.6142 0.59082 100 22.9766 100 50.5908ZM9.08144 50.5908C9.08144 73.1895 27.4013 91.5094 50 91.5094C72.5987 91.5094 90.9186 73.1895 90.9186 50.5908C90.9186 27.9921 72.5987 9.67226 50 9.67226C27.4013 9.67226 9.08144 27.9921 9.08144 50.5908Z"
                fill="currentColor" />
              <path
                d="M93.9676 39.0409C96.393 38.4038 97.8624 35.9116 97.0079 33.5539C95.2932 28.8227 92.871 24.3692 89.8167 20.348C85.8452 15.1192 80.8826 10.7238 75.2124 7.41289C69.5422 4.10194 63.2754 1.94025 56.7698 1.05124C51.7666 0.367541 46.6976 0.446843 41.7345 1.27873C39.2613 1.69328 37.813 4.19778 38.4501 6.62326C39.0873 9.04874 41.5694 10.4717 44.0505 10.1071C47.8511 9.54855 51.7191 9.52689 55.5402 10.0491C60.8642 10.7766 65.9928 12.5457 70.6331 15.2552C75.2735 17.9648 79.3347 21.5619 82.5849 25.841C84.9175 28.9121 86.7997 32.2913 88.1811 35.8758C89.083 38.2158 91.5421 39.6781 93.9676 39.0409Z"
                fill="currentFill" />
            </svg>
            <span class="sr-only">Loading...</span>
          </div>

        </div>

      </div>
      <!-- buttons -->
      <div class="flex justify-between mt-[0] mx-[0] mb-[20px]">
        <button class="w-full bg-[#4ec5c1] text-[#fff] border-[none] rounded-[10px] p-[10px] mx-[5px] my-[0]">
          Limpiar Completados
        </button>
        <button class="w-full bg-[#4ec5c1] text-[#fff] border-[none] rounded-[10px] p-[10px] mx-[5px] my-[0]">
          Limpiar Todo
        </button>
      </div>
      <!-- pending task -->
      <div class="flex justify-between mt-[0] mx-[0] mb-[20px]">
        <span>Tareas Pendientes: {{ pendingTasks }} </span>
      </div>
    </div>
    <div class="flex justify-center mt-[20px]  w-full">
      <button
        @click="closeSession"
        class="px-4 py-2 text-white bg-red-500 rounded-lg hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-opacity-50 w-full mt-4"
      >
        Cerrar Sesión
      </button>
    </div>
  </div>


</template>

