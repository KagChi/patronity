import { PropsWithChildren } from "react";

export function BaseLayout({ children }: PropsWithChildren) {
    return (
        <main>
            {children}
        </main>
    );
}
