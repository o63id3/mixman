<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import UpdateFormLayout from '@/Components/forms/UpdateFormLayout.vue'

import { formSchema } from './definitions'
import CardForm from './Partials/CardForm.vue'

import { toast } from '@/Components/ui/toast'
import { Badge } from '@/Components/ui/badge'
import { DeleteLink, SecondaryLink } from '@/Components/links'
import BreadcrumbsGenerator from '@/Components/BreadcrumbsGenerator.vue'

import { Card } from '@/types'

const props = defineProps<{
  card: Card
}>()

const initialValues = {
  name: props.card.name,
  price_for_consumer: props.card.price_for_consumer,
  notes: props.card.notes,
}

const breadcrumbs = [
  {
    label: 'الرئيسة',
    route: route('dashboard'),
  },
  {
    label: 'الكروت',
    route: route('cards.index'),
  },
  {
    label: props.card.name,
  },
]
</script>

<template>
  <AuthenticatedLayout>
    <template #secondaryHeader>
      <div class="flex items-center gap-2">
        <BreadcrumbsGenerator :breadcrumbs="breadcrumbs" />
        <Badge
          variant="outline"
          class="mr-auto sm:ml-0"
          :class="[card.active ? 'text-green-500' : 'text-red-500']"
        >
          {{ card.active ? 'فعال' : 'معطل' }}
        </Badge>
      </div>
    </template>

    <UpdateFormLayout
      :form-schema="formSchema"
      :initial-values="initialValues"
      :route="route('cards.update', card.id)"
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
