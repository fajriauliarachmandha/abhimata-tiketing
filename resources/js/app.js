import { createApp, h, nextTick } from "vue";
import { createInertiaApp, router } from "@inertiajs/vue3";
import { resolvePageComponent } from "laravel-vite-plugin/inertia-helpers";
import { initFlowbite } from "flowbite";
import ThemeProvider from "./Layouts/ThemeProvider.vue";
import SidebarProvider from "./Layouts/SidebarProvider.vue";

router.on("finish", () => {
    nextTick(() => {
        initFlowbite();
    });
});

createInertiaApp({
    progress: {
        color: "#4B5563",
        showSpinner: false,
    },
    resolve: (name) =>
        resolvePageComponent(
            `./Pages/${name}.vue`,
            import.meta.glob("./Pages/**/*.vue"),
        ),
    setup({ el, App, props, plugin }) {
        createApp({
            render: () =>
                h(ThemeProvider, null, {
                    default: () =>
                        h(SidebarProvider, null, {
                            default: () => h(App, props),
                        }),
                }),
        })
            .use(plugin)
            .mount(el);
    },
});
