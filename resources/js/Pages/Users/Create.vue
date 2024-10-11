<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'

import { useForm } from 'vee-validate'
import { formSchema } from './definitions'
import UserForm from './Partials/UserForm.vue'
import { useSubmit } from '@/Composables/submit'

import CreateFormLayout from '@/Components/forms/CreateFormLayout.vue'
import { toast } from '@/Components/ui/toast'

import { Network } from '@/types'

defineProps<{
  networks: Array<Network>
}>()

const { handleSubmit, setErrors, values } = useForm({
  validationSchema: formSchema,
  initialValues: {
    percentage: 10,
  },
})

const { submit, loading } = useSubmit(route('users.store'), {
  method: 'post',
  onSuccess: () => toast({ title: 'تم إنشاء المستخدم' }),
  onError: (errors) => setErrors(errors),
})
const onSubmit = handleSubmit(submit)
</script>

<template>
  <AuthenticatedLayout>
    <template #header>
      <h2 class="text-xl font-semibold leading-tight text-gray-800">
        مستخدم جديد
      </h2>
    </template>

    <CreateFormLayout @submit="onSubmit" :loading="loading">
      <UserForm :role="values.role" :networks="networks" />
    </CreateFormLayout>
  </AuthenticatedLayout>
</template>
