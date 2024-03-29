import { createI18n } from "vue-i18n"
import de from './locales/de.yaml'
import en from './locales/en.yaml'

export default createI18n({
	locale: import.meta.env.VITE_DEFAULT_LOCALE,
	fallbackLocale: import.meta.env.VITE_FALLBACK_LOCALE,
	legacy: false,
	globalInjection: true,
	messages: {
		de,
		en,
	}
})