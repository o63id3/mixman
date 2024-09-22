<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { Head, Link, router } from '@inertiajs/vue3'

import { useForm } from 'vee-validate'
import { toTypedSchema } from '@vee-validate/zod'
import * as z from 'zod'

import { toast } from '@/Components/ui/toast'
import { Card } from '@/types'
import CardForm from './Partials/CardForm.vue'
import UpdateFormLayout from '@/Components/forms/UpdateFormLayout.vue'
import { cn } from '@/lib/utils'
import { buttonVariants } from '@/Components/ui/button'
import DeleteLink from '@/Components/links/DeleteLink.vue'

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
    price_for_seller: z.number({ message: 'هذا الحقل مطلوب' }).min(0),
    notes: z.string().optional(),
  }),
)

const { handleSubmit, setErrors } = useForm({
  validationSchema: formSchema,
  initialValues: {
    name: props.card.name,
    price_for_consumer: props.card.price_for_consumer,
    price_for_seller: props.card.price_for_seller,
    active: props.card.active,
    notes: props.card.notes ?? undefined,
  },
})

const onSubmit = handleSubmit((values) => {
  router.patch(route('cards.update', props.card.id), values, {
    preserveScroll: true,
    onSuccess: () => toast({ title: 'تم تعديل الكرت' }),
    onError: (errors) => setErrors(errors),
  })
})
</script>

<template>
  <Head title="Cards" />

  <AuthenticatedLayout>
    <template #header>
      <h2 class="text-xl font-semibold leading-tight text-gray-800">
        {{ card.name }}
      </h2>
    </template>

    <UpdateFormLayout @submit="onSubmit" can-update :can-delete="can.delete">
      <template #deleteBtn>
        <DeleteLink
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
