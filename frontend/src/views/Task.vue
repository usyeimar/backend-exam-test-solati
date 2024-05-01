<script setup lang="ts">
import { computed, onMounted, ref } from 'vue'
import { TrashIcon, PlusIcon } from '@heroicons/vue/24/solid'
import { Api } from '@/api/api'
import { toast } from 'vue3-toastify'

const api = new Api()
const newTask = ref('')

const tasks = ref([])

const pendingTasks = computed(() => {
  return tasks.value.filter((task: { completed: boolean }) => !task.completed).length
})


const addTask = () => {
  const newTask = document.getElementById('new_task') as HTMLInputElement
  if (newTask.value) {
    tasks.value.push({
      title: newTask.value,
      completed: false
    })
    newTask.value = ''
  }
}

const deleteTask = async (id: string) => {
  const index = tasks.value.findIndex((task: { id: number }) => task.id === id)
  tasks.value.splice(index, 1)

  await api.deleteTask(id).then(() => {
    toast('Task deleted', { type: 'success',delay: 100,autoClose: true})
  }).catch((error) => {
    console.log('Error', error)
  })

}


onMounted(async () => {
  const { data } = await api.getAllTasks()
  tasks.value.push(...data)
})

</script>
<template>
  <div class="max-w-[700px]  mx-auto my-0 px-[15px] py-0  pt-4">
    <div class="bg-[#fff] rounded-[25px] p-[30px] [box-shadow:0px_0px_40px_0px_rgba(0,0,0,0.1)]">
      <!-- title -->
      <div class="text-center mt-[0] mx-[0] mb-[20px]">
        <h1>To Do List</h1>
      </div>
      <!-- form -->
      <div class="relative mt-[0] mx-[0] mb-[30px]">
        <input type="text" id="new_task"
               v-model="newTask"
               @keyup.enter="addTask"
               class="w-full h-[50px] p-[15px] bg-[#f3f3f3] text-[#333] border-[1px] border-[transparent] rounded-[10px] [transition:border_0.3s_linear]"
               placeholder="New Task"
               required />
        <PlusIcon
          class="h-[20px] w-[20px] text-gray-400 cursor-pointer absolute top-[50%] right-[20px] transform [-translate-y-1/2]"
          @click="addTask" />
      </div>

      <!-- task lists -->
      <div class="px-[10px] py-[0]">
        <ul>
          <li v-for="task in tasks" class="flex justify-between mt-[0] mx-[0] mb-[20px]">
            <button>
              {{ task.title }}
            </button>
            <TrashIcon class="h-[20px] w-[20px] text-gray-400 cursor-pointer" @click="deleteTask(task.uuid)" />
          </li>
        </ul>
      </div>
      <!-- buttons -->
      <div class="flex justify-between mt-[0] mx-[0] mb-[20px]">
        <button class="w-full bg-[#4ec5c1] text-[#fff] border-[none] rounded-[10px] p-[10px] mx-[5px] my-[0]">
          Clear completed
        </button>
        <button class="w-full bg-[#4ec5c1] text-[#fff] border-[none] rounded-[10px] p-[10px] mx-[5px] my-[0]">
          Clear all
        </button>
      </div>
      <!-- pending task -->
      <div class="flex justify-between mt-[0] mx-[0] mb-[20px]">
        <span>Pending Tasks: {{ pendingTasks }} </span>
      </div>
    </div>
  </div>

</template>

