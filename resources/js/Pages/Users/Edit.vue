<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'

import { useForm } from 'vee-validate'
import { useSubmit } from '@/Composables/submit'
import { formSchema } from './definitions'
import UserForm from './Partials/UserForm.vue'

import { toast } from '@/Components/ui/toast'
import { DeleteLink, SecondaryLink } from '@/Components/links'
import UpdateFormLayout from '@/Components/forms/UpdateFormLayout.vue'

import { Network, User } from '@/types'

const props = defineProps<{
  user: User
  networks: Array<Network>
  can: {
    update: boolean
    delete: boolean
  }
}>()

const { handleSubmit, setErrors, values } = useForm({
  validationSchema: formSchema,
  initialValues: {
    name: props.user.name,
    username: props.user.username,
    telegram: props.user.telegram,
    role: props.user.role,
    network_id: props.user.network ? String(props.user.network?.id) : undefined,
    percentage: props.user.percentage,
    contact_info: props.user.contact_info,
  },
})

const { submit, loading } = useSubmit(route('users.update', props.user.id), {
  method: 'patch',
  onSuccess: () => toast({ title: 'تم تعديل المستخدم' }),
  onError: (errors) => setErrors(errors),
})
const onSubmit = handleSubmit(submit)
</script>

<template>
  <AuthenticatedLayout>
    <template #header>
      <h2 class="text-xl font-semibold leading-tight text-gray-800">
        {{ user.name }}
      </h2>
    </template>

    <UpdateFormLayout
      @submit="onSubmit"
      :loading="loading"
      :can-update="can.update"
    >
      <template #buttons>
        <DeleteLink
          v-if="user.active"
          :href="route('users.deactivate', user.id)"
          @success="toast({ title: 'تم تعطيل الحساب' })"
          method="POST"
        >
          تعطيل
        </DeleteLink>
        <SecondaryLink
          v-else
          method="DELETE"
          :href="route('users.activate', user.id)"
          @success="toast({ title: 'تم تفعيل الحساب' })"
        >
          تفعيل
        </SecondaryLink>
      </template>

      <UserForm
        :disabled="!can.update"
        :role="values.role"
        :networks="networks"
      />
    </UpdateFormLayout>
  </AuthenticatedLayout>
</template>
