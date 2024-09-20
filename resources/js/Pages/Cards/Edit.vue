<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { Head, Link, router } from '@inertiajs/vue3'

import { useForm } from 'vee-validate'
import { toTypedSchema } from '@vee-validate/zod'
import * as z from 'zod'

import { Button, buttonVariants } from '@/Components/ui/button'
import {
  FormControl,
  FormField,
  FormItem,
  FormLabel,
  FormMessage,
} from '@/Components/ui/form'
import { Input } from '@/Components/ui/input'
import { toast } from '@/Components/ui/toast'
import Textarea from '@/Components/ui/textarea/Textarea.vue'
import { Card } from '@/types'
import { cn } from '@/lib/utils'

const props = defineProps<{
  card: Card
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
  console.log(values)

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

    <div class="py-12">
      <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
        <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
          <div class="p-6 text-gray-900">
            <form class="space-y-6" @submit="onSubmit">
              <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                <FormField v-slot="{ componentField }" name="name">
                  <FormItem class="col-span-2">
                    <FormLabel>اسم الكرت</FormLabel>
                    <FormControl>
                      <Input type="text" v-bind="componentField" />
                    </FormControl>
                    <FormMessage />
                  </FormItem>
                </FormField>
                <FormField
                  v-slot="{ componentField }"
                  name="price_for_consumer"
                >
                  <FormItem>
                    <FormLabel>السعر للمستهلك</FormLabel>
                    <FormControl>
                      <Input
                        type="number"
                        step="0.01"
                        v-bind="componentField"
                      />
                    </FormControl>
                    <FormMessage />
                  </FormItem>
                </FormField>
                <FormField v-slot="{ componentField }" name="price_for_seller">
                  <FormItem>
                    <FormLabel>السعر للبائع</FormLabel>
                    <FormControl>
                      <Input
                        type="number"
                        step="0.01"
                        v-bind="componentField"
                      />
                    </FormControl>
                    <FormMessage />
                  </FormItem>
                </FormField>
                <FormField v-slot="{ componentField }" name="notes">
                  <FormItem class="md:col-span-2">
                    <FormLabel>ملاحظات</FormLabel>
                    <FormControl>
                      <Textarea v-bind="componentField" />
                    </FormControl>
                    <FormMessage />
                  </FormItem>
                </FormField>
              </div>
              <div class="flex gap-2">
                <Button type="submit"> تعديل </Button>
                <Link
                  :href="route('cards.destroy', card.id)"
                  method="delete"
                  as="button"
                  type="button"
                  :class="cn(buttonVariants({ variant: 'destructive' }))"
                  @success="toast({ title: 'تم حذف الكرت' })"
                >
                  حذف
                </Link>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>
