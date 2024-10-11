<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'

import { formSchema } from './definitions'

import { toast } from '@/Components/ui/toast'
import CreateFormLayout from '@/Components/forms/CreateFormLayout.vue'
import PaymentForm from './Partials/PaymentForm.vue'

import { User } from '@/types'

defineProps<{
  users: Array<User>
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
      :route="route('payments.store')"
      @success="toast({ title: 'تم إدخال الدفعة المالية' })"
    >
      <template #default="{ values, setFieldValue }">
        <PaymentForm
          :users="users"
          :selected="values.user_id"
          @select="(selected: number) => setFieldValue('user_id', selected)"
        />
      </template>
    </CreateFormLayout>
  </AuthenticatedLayout>
</template>
