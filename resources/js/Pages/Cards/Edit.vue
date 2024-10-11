<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { Head } from '@inertiajs/vue3'

import { useForm } from 'vee-validate'
import { toTypedSchema } from '@vee-validate/zod'
import * as z from 'zod'

import { toast } from '@/Components/ui/toast'
import { Card } from '@/types'
import CardForm from './Partials/CardForm.vue'
import UpdateFormLayout from '@/Components/forms/UpdateFormLayout.vue'
import DeleteLink from '@/Components/links/DeleteLink.vue'
import { useSubmit } from '@/Composables/submit'

const props = defineProps<{
  card: Card
  can: {
    delete: boolean
  }
}>()

const formSchema = toTypedSchema(
  z.object({
    name: z
      .string({ message: 'هذا الحقل مطلوب' })
      .min(2, { message: 'الاسم يجيب ان يكون حرفين على الاقل' }),
    active: z.boolean(),
    price_for_consumer: z.number({ message: 'هذا الحقل مطلوب' }).min(0),
    notes: z.string().nullable().optional(),
  }),
)

const { handleSubmit, setErrors } = useForm({
  validationSchema: formSchema,
  initialValues: {
    name: props.card.name,
    price_for_consumer: props.card.price_for_consumer,
    active: props.card.active,
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
  <Head title="Cards" />

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
      </template>

      <CardForm />
    </UpdateFormLayout>
  </AuthenticatedLayout>
</template>
