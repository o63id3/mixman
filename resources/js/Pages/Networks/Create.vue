<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'

import { formSchema } from './definitions'
import NetworkForm from './Partials/NetworkForm.vue'

import CreateFormLayout from '@/Components/forms/CreateFormLayout.vue'
import { toast } from '@/Components/ui/toast'
</script>

<template>
  <AuthenticatedLayout>
    <template #header>
      <h2 class="text-xl font-semibold leading-tight text-gray-800">
        شبكة جديدة
      </h2>
    </template>

    <CreateFormLayout
      :form-schema="formSchema"
      route="networks.store"
      @success="toast({ title: 'تم إنشاء الشبكة' })"
    >
      <NetworkForm />
    </CreateFormLayout>
  </AuthenticatedLayout>
</template>
