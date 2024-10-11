<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'

import { formSchema } from './definitions'
import UserForm from './Partials/UserForm.vue'

import CreateFormLayout from '@/Components/forms/CreateFormLayout.vue'
import { toast } from '@/Components/ui/toast'

import { Network } from '@/types'

defineProps<{
  networks: Array<Network>
}>()

const initialValues = {
  percentage: 10,
}
</script>

<template>
  <AuthenticatedLayout>
    <template #header>
      <h2 class="text-xl font-semibold leading-tight text-gray-800">
        مستخدم جديد
      </h2>
    </template>

    <CreateFormLayout
      :form-schema="formSchema"
      :initial-values="initialValues"
      route="users.store"
      @success="toast({ title: 'تم إنشاء المستخدم' })"
    >
      <template #default="{ values }">
        <UserForm :role="values.role" :networks="networks" />
      </template>
    </CreateFormLayout>
  </AuthenticatedLayout>
</template>
