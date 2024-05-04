<script setup lang="ts">
import { FwbButton } from 'flowbite-vue'
import http from '@/services/http'
import { object, string, ref as refYup } from 'yup'
import { ErrorMessage, Field, Form, useForm, useField } from 'vee-validate'
import { toast } from 'vue3-toastify'
import { useRouter } from 'vue-router'
import { isAxiosError } from 'axios'
import { ref } from 'vue'


const router = useRouter()
const isLoading = ref(false)

const formSchema = object({
  email: string()
    .email('Ingrese un correo valido')
    .required('El correo electronico es requerido')
    .label('Correo'),
  password: string().required('La contraseña es requerida').label('Contraseña'),
  name: string().required('El nombre es requerido').label('Nombre'),
  password_confirmation: string()
    .required('La confirmacion de la contraseña es requerida')
    .label('Confirmacion de la contraseña')
    .oneOf([refYup('password'), null], 'Las contraseñas no coinciden')
})

const { errors: errorTest, handleSubmit } = useForm({
  validationSchema: formSchema,
  initialValues: {
    name: '',
    email: '',
    password: '',
    password_confirmation: ''
  }
})


const { value: emailValue } = useField('email')
const { value: passwordValue } = useField('password')
const { value: passwordConfirmValue } = useField('password_confirmation')
const { value: nameValue } = useField('name')


const onSubmit = handleSubmit(async (values) => {
  isLoading.value = true
  try {
    await http
      .post('/api/v1/auth/register', {
        email: values.email,
        password: values.password,
        name: values.name,
        password_confirmation: values.password_confirmation
      })
      .then(({ data }) => {
        isLoading.value = false
        router.push({ name: 'verify-email' }) // Redirect to Home
      })
  } catch (error) {
    isLoading.value = false
    if (isAxiosError(error)) {
      const { data } = error.response!
      const { errors } = data
      errors.forEach((error: any) => {
        toast.error(error.detail)
      })
    } else {
      console.log('Error', error)
    }
  }
})
</script>
<template>
  <div
    class="flex flex-col bg-slate-100 items-center justify-center px-6 pt-8 mx-auto md:h-screen pt:mt-0 dark:bg-gray-900"
  >
    <!-- Card -->
    <div
      class="w-full max-w-md p-x10 py-20 p-6 space-y-8 sm:p-8 bg-white rounded-3xl border-2 shadow dark:bg-gray-800"
    >
      <h1 class="text-5xl text-center font-bold text-gray-900 dark:text-white">
        Registrarse
      </h1>
      <form
        class="mt-8 space-y-6"
        @submit="onSubmit"
        :validation-schema="formSchema"
      >
        <!-- Name  Input-->
        <div>
          <input
            v-model="nameValue"
            type="text"
            name="name"
            id="name"
            placeholder="Nombre completo"
            class="w-full border-2 border-gray-100 rounded-xl p-4 mt-1 bg-transparent"
            :class="{
              'border-red-500 focus:ring-red-500 dark:bg-gray-700 focus:border-red-500':
                errorTest.name,
            }"
          />
          <ErrorMessage
            class="mt-2 text-sm text-red-600 dark:text-red-500 font-semibold"
            name="name"
          />
        </div>

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

        <!-- Password Input Confirm -->
        <div>
          <input
            v-model="passwordConfirmValue"
            type="password"
            name="password_confirmation"
            id="password_confirmation"
            placeholder="Confirmacion de la contraseña"
            class="w-full border-2 border-gray-100 rounded-xl p-4 mt-1 bg-transparent"
            :class="{
              'border-red-500 focus:ring-red-500 dark:bg-gray-700 focus:border-red-500 ':
                errorTest.password_confirmation            }"
          />
          <ErrorMessage
            name="password_confirmation"
            class="mt-2 text-sm text-red-600 dark:text-red-500 font-semibold"
          />
        </div>

        <div class="mt-8 flex flex-col gap-y-4">
          <button
            :disabled="isLoading"
            type="submit"
            class="active:scale-[.98] active:duration-75 transition-all hover:scale-[1.01] ease-in-out transform py-4 bg-green-700 rounded-xl text-white font-semibold text-lg"
          >
            <span v-if="isLoading" class="mr-2">
              Resgistrando tus datos...
            </span>
            <span v-else>
              Registrarse
            </span>
          </button>

        </div>

        <div class="text-sm font-medium text-gray-500 dark:text-gray-400">
          ¿Ya tienes una cuenta?
          <RouterLink to="/login"
                      class="text-primary-700 hover:underline dark:text-primary-500  hover:text-emerald-600">
            Iniciar sesion
          </RouterLink>
        </div>
      </form>
    </div>
  </div>
</template>