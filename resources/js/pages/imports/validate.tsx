import { Button } from "@/components/ui/button";
import { dashboard } from "@/routes";
import { store } from "@/routes/imports/validate";
import { Head, useForm } from "@inertiajs/react";
import { FormEventHandler } from "react";

type ValidateProps = {
    import: {
        id: number;
        original_filename: string;
        status: string;
        total_rows: number | null;
        valid_rows: number | null;
        invalid_rows: number | null;
        duplicate_rows: number | null;
    };
};

export default function ImportValidate({
    import: importRecord,
}: ValidateProps) {
    const { post, processing } = useForm({});

    const submit: FormEventHandler = (e) => {
        e.preventDefault();
        post(store.url({ import: importRecord.id }));
    };

    const alreadyValidated = importRecord.status === "validated";

    return (
        <>
            <Head title="Validate Participants" />
            <div className="flex h-full flex-1 flex-col gap-4 p-4">
                <div className="max-w-xl">
                    <h1 className="text-xl font-semibold">
                        Validate Participants
                    </h1>
                    <p className="mt-1 text-sm text-muted-foreground">
                        Run validation on &quot;{importRecord.original_filename}
                        &quot; to check for missing fields, invalid emails, and
                        duplicates.
                    </p>

                    {alreadyValidated && (
                        <div className="mt-6 grid grid-cols-2 gap-4 rounded-lg border p-4 text-sm">
                            <div>
                                Total rows:{" "}
                                <strong>{importRecord.total_rows}</strong>
                            </div>
                            <div>
                                Valid:{" "}
                                <strong>{importRecord.valid_rows}</strong>
                            </div>
                            <div>
                                Invalid:{" "}
                                <strong>{importRecord.invalid_rows}</strong>
                            </div>
                            <div>
                                Duplicate:{" "}
                                <strong>{importRecord.duplicate_rows}</strong>
                            </div>
                        </div>
                    )}

                    <form onSubmit={submit} className="mt-6">
                        <Button type="submit" disabled={processing}>
                            {processing
                                ? "Validating..."
                                : alreadyValidated
                                  ? "Re-run Validation"
                                  : "Run Validation"}
                        </Button>
                    </form>
                </div>
            </div>
        </>
    );
}

ImportValidate.layout = {
    breadcrumbs: [
        { title: "Dashboard", href: dashboard() },
        { title: "Upload Participants", href: "/imports/create" },
        { title: "Validate", href: "" },
    ],
};
