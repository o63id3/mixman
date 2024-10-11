<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'

import { formSchema } from './definitions'
import CardForm from './Partials/CardForm.vue'

import { toast } from '@/Components/ui/toast'
import UpdateFormLayout from '@/Components/forms/UpdateFormLayout.vue'
import { DeleteLink, SecondaryLink } from '@/Components/links'

import { Card } from '@/types'

const props = defineProps<{
  card: Card
}>()

const initialValues = {
  name: props.card.name,
  price_for_consumer: props.card.price_for_consumer,
  notes: props.card.notes,
}
</script>

<template>
  <AuthenticatedLayout>
    <template #header>
      <h2 class="text-xl font-semibold leading-tight text-gray-800">
        {{ card.name }}
      </h2>
    </template>

    <UpdateFormLayout
      :form-schema="formSchema"
      :initial-values="initialValues"
      :route="route('cards.update', card.id)"
      @success="toast({ title: 'تم تعديل الكرت' })"
      can-update
    >
      <template #buttons>
        <DeleteLink
          v-if="card.active"
          :href="route('cards.deactivate', card.id)"
          @success="toast({ title: 'تم تعطيل الكرت' })"
          method="POST"
        >
          تعطيل
        </DeleteLink>
        <SecondaryLink
          v-else
          method="DELETE"
          :href="route('cards.activate', card.id)"
          @success="toast({ title: 'تم تفعيل الكرت' })"
        >
          تفعيل
        </SecondaryLink>
      </template>

      <CardForm />
    </UpdateFormLayout>
  </AuthenticatedLayout>
</template>
