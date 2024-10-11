<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'

import { useForm } from 'vee-validate'
import { formSchema } from './definitions'
import { useSubmit } from '@/Composables/submit'
import CardForm from './Partials/CardForm.vue'

import { toast } from '@/Components/ui/toast'
import { Card } from '@/types'
import UpdateFormLayout from '@/Components/forms/UpdateFormLayout.vue'
import { DeleteLink, SecondaryLink } from '@/Components/links'

const props = defineProps<{
  card: Card
  can: {
    delete: boolean
  }
}>()

const { handleSubmit, setErrors } = useForm({
  validationSchema: formSchema,
  initialValues: {
    name: props.card.name,
    price_for_consumer: props.card.price_for_consumer,
    notes: props.card.notes,
  },
})

const { submit, loading } = useSubmit(route('cards.update', props.card.id), {
  method: 'patch',
  onSuccess: () => toast({ title: 'تم تعديل الكرت' }),
  onError: (errors) => setErrors(errors),
})
const onSubmit = handleSubmit(submit)
</script>

<template>
  <AuthenticatedLayout>
    <template #header>
      <h2 class="text-xl font-semibold leading-tight text-gray-800">
        {{ card.name }}
      </h2>
    </template>

    <UpdateFormLayout @submit="onSubmit" :loading="loading" can-update>
      <template #buttons>
        <DeleteLink
          v-if="can.delete"
          :href="route('cards.destroy', card.id)"
          @success="toast({ title: 'تم حذف الطلب' })"
        >
          حذف
        </DeleteLink>
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
