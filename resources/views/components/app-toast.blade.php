<div>
    @persist('mary-toaster')
        <div
            x-cloak
            x-data="{
                show: false,
                toast: {},
                timer: null,
                interval: null,
                maxProgress: 100,
                progress: 100,
                startTime: 0,
                remaining: 0,
                start(toast) {
                    this.clearTimers();
                    this.toast = toast;
                    this.progress = this.maxProgress;
                    this.remaining = toast.timeout;
                    setTimeout(() => this.show = true, 50);
                    this.startProgress();
                    this.startCloseTimer();
                },
                startProgress() {
                    if (this.toast.noProgress) return;

                    const intervalRefreshRate = 8;
                    const step = this.progress / (this.remaining / intervalRefreshRate);

                    this.startTime = Date.now();

                    this.interval = setInterval(() => {
                        this.progress -= step;
                        if (this.progress <= 0) {
                            this.progress = 0;
                            clearInterval(this.interval);
                        }
                    }, intervalRefreshRate);
                },
                startCloseTimer() {
                    this.startTime = Date.now();

                    this.timer = setTimeout(() => {
                        this.close();
                    }, this.remaining);
                },
                pause() {
                    if (!this.show) return;

                    const elapsed = Date.now() - this.startTime;
                    this.remaining -= elapsed;
                    this.clearTimers();
                },
                resume() {
                    if (!this.show || this.remaining <= 0) return;

                    this.startProgress();
                    this.startCloseTimer();
                },
                close() {
                    this.show = false;
                    this.clearTimers();
                },
                clearTimers() {
                    clearTimeout(this.timer);
                    clearInterval(this.interval);
                    this.timer = null;
                    this.interval = null;
                }
            }"
            @mary-toast.window="start($event.detail.toast)"
        >
            <div
                class="toast !whitespace-normal rounded-md fixed cursor-pointer z-[999] overflow-hidden"
                :class="toast.position || '{{ $position }}'"
                x-show="show"
                @mouseenter="pause()"
                @mouseleave="resume()"
                x-classes="alert alert-success alert-warning alert-error alert-info top-10 end-10 toast toast-top toast-bottom toast-center toast-end toast-middle toast-start"
                @click="show = false; clearInterval(interval)"
            >
                <div class="alert gap-2" :class="toast.css">
                    <div x-html="toast.icon" class="hidden sm:inline-block"></div>
                    <div class="grid">
                        <div x-html="toast.title" class="font-bold"></div>
                        <div x-html="toast.description" class="text-xs"></div>
                    </div>
                </div>

                <progress
                    x-show="!toast.noProgress"
                    class="-mt-3 h-1 w-full progress"
                    :class="toast.progressClass"
                    :max="maxProgress"
                    :value="progress"
                ></progress>
            </div>
        </div>
    @endpersist
</div>
