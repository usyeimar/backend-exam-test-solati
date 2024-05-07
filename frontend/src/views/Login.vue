<script setup lang="ts">
import http from '@/services/http'
import {object, string} from 'yup'
import {ErrorMessage, Field, Form, useForm, useField} from 'vee-validate'
import {toast} from 'vue3-toastify'
import {useRouter} from 'vue-router'
import {isAxiosError} from 'axios'
import {ref} from 'vue'


const router = useRouter()
const isLoading = ref(false)

const formSchema = object({
  email: string()
      .email('Ingrese un correo valido')
      .required('El correo electronico es requerido')
      .label('Correo'),
  password: string().required('La contraseña es requerida').label('Contraseña')
})


const {errors: errorTest, handleSubmit} = useForm({
  validationSchema: formSchema,
  initialValues: {
    email: '',
    password: ''
  }
})
const {value: emailValue} = useField('email')
const {value: passwordValue} = useField('password')

const onSubmit = handleSubmit(async (values) => {
  isLoading.value = true
  try {
    await http
        .post('/api/v1/auth/login', {
          email: values.email,
          password: values.password
        })
        .then(({data}) => {
          isLoading.value = false
          localStorage.setItem('token', JSON.stringify(data.data.token))
          toast.success('Inicio de sesion exitoso', {delay: 2000, position: 'top-right'})

          router.push({name: 'dashboard'})
        })
  } catch (error) {
    isLoading.value = false
    if (isAxiosError(error)) {
      const {data} = error.response!
      const {errors} = data
      errors.forEach((error: any) => {
        toast.error(error.detail)
      })
    }
  }
})
</script>
<template>
  <div
      class="flex flex-col bg-slate-100 items-center justify-center px-6 pt-8 mx-auto md:h-screen pt:mt-0 dark:bg-gray-900">
    <!-- Card -->
    <div class="w-full max-w-md p-x10 py-20 p-6 space-y-8 sm:p-8 bg-white rounded-3xl border shadow dark:bg-gray-800">
      <h1 class="text-5xl text-center font-bold text-gray-900 dark:text-white">
        Iniciar Sesion
      </h1>
      <form
          class="mt-8 space-y-6"
          @submit="onSubmit"
          :validation-schema="formSchema"
      >
        <!-- Email  Input-->
        <div>
          <input
              v-model="emailValue"
              type="text"
              name="email"
              id="email"
              placeholder="Correo Electronico"
              class="w-full border-2 border-gray-100 rounded-xl p-4 mt-1 bg-transparent"
              :class="{
              'border-red-500 focus:ring-red-500 dark:bg-gray-700 focus:border-red-500':
                errorTest.email,
            }"
          />
          <ErrorMessage
              class="mt-2 text-sm text-red-600 dark:text-red-500 font-semibold"
              name="email"
          />
        </div>

        <!-- Password Input -->
        <div>
          <input
              v-model="passwordValue"
              type="password"
              name="password"
              id="password"
              placeholder="Contraseña"
              class="w-full border-2 border-gray-100 rounded-xl p-4 mt-1 bg-transparent"
              :class="{
              'border-red-500 focus:ring-red-500 dark:bg-gray-700 focus:border-red-500 ':
                errorTest.password,
            }"
          />
          <ErrorMessage
              name="password"
              class="mt-2 text-sm text-red-600 dark:text-red-500 font-semibold"
          />
        </div>

        <div class="mt-8 flex flex-col gap-y-4">


          <button
              :disabled="isLoading"
              type="submit"
              class="active:scale-[.98] active:duration-75 transition-all hover:scale-[1.01] ease-in-out transform py-4 bg-green-700 rounded-xl text-white font-semibold text-lg"
          >
            <span v-if="isLoading" class="mr-2">Cargando...</span>
            <span v-else>Iniciar sesion</span>
          </button>

        </div>

        <div class="text-sm font-medium text-gray-500 dark:text-gray-400">
          No estas Registrado?
          <RouterLink to="/register"
                      class="text-primary-700 hover:underline dark:text-primary-500  hover:text-emerald-600">
            Crear Una cuenta
          </RouterLink>
        </div>
      </form>
    </div>
  </div>
</template>