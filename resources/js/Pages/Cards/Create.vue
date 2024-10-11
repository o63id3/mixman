<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'

import { formSchema } from './definitions'
import CardForm from './Partials/CardForm.vue'

import CreateFormLayout from '@/Components/forms/CreateFormLayout.vue'

import { toast } from '@/Components/ui/toast'
</script>

<template>
  <AuthenticatedLayout>
    <template #header>
      <h2 class="text-xl font-semibold leading-tight text-gray-800">
        كرت جديد
      </h2>
    </template>

    <CreateFormLayout
      :form-schema="formSchema"
      :route="route('cards.store')"
      @success="toast({ title: 'تم إنشاء الكرت' })"
    >
      <CardForm />
    </CreateFormLayout>
  </AuthenticatedLayout>
</template>
