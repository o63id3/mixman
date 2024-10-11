<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'

import { formSchema } from './definitions'
import ExpenseForm from './Partials/ExpenseForm.vue'

import CreateFormLayout from '@/Components/forms/CreateFormLayout.vue'
import { toast } from '@/Components/ui/toast'

import { Network } from '@/types'

defineProps<{
  networks: Array<Network>
}>()
</script>

<template>
  <AuthenticatedLayout>
    <template #header>
      <h2 class="text-xl font-semibold leading-tight text-gray-800">
        دفعة مالية
      </h2>
    </template>

    <CreateFormLayout
      :form-schema="formSchema"
      route="expenses.store"
      @success="toast({ title: 'تم إنشاء المصروف' })"
    >
      <ExpenseForm :networks="networks" />
    </CreateFormLayout>
  </AuthenticatedLayout>
</template>
