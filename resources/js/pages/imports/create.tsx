import InputError from "@/components/input-error";
import { Button } from "@/components/ui/button";
import { Input } from "@/components/ui/input";
import { Label } from "@/components/ui/label";
import { dashboard } from "@/routes";
import { store } from "@/routes/imports";
import { Head, useForm } from "@inertiajs/react";
import { FormEventHandler } from "react";

type ImportForm = {
    file: File | null;
};

export default function CreateImport() {
    const { data, setData, post, processing, progress, errors, reset } =
        useForm<ImportForm>({
            file: null,
        });

    const submit: FormEventHandler = (e) => {
        e.preventDefault();
        post(store().url, {
            forceFormData: true,
            onSuccess: () => reset("file"),
        });
    };

    return (
        <>
            <Head title="Upload Participants" />
            <div className="flex h-full flex-1 flex-col gap-4 p-4">
                <div className="max-w-xl">
                    <h1 className="text-xl font-semibold">
                        Upload Participant File
                    </h1>
                    <p className="mt-1 text-sm text-muted-foreground">
                        Upload a CSV or Excel file containing participant data.
                        You&apos;ll map columns and review the data in the next
                        step.
                    </p>

                    <form onSubmit={submit} className="mt-6 space-y-6">
                        <div className="grid gap-2">
                            <Label htmlFor="file">Participant file</Label>
                            <Input
                                id="file"
                                type="file"
                                accept=".csv,.xlsx,.xls"
                                onChange={(e) =>
                                    setData("file", e.target.files?.[0] ?? null)
                                }
                            />
                            <InputError message={errors.file} />
                        </div>

                        {progress && (
                            <div className="text-sm text-muted-foreground">
                                Uploading... {progress.percentage}%
                            </div>
                        )}

                        <Button
                            type="submit"
                            disabled={processing || !data.file}
                        >
                            {processing ? "Uploading..." : "Upload File"}
                        </Button>
                    </form>
                </div>
            </div>
        </>
    );
}

CreateImport.layout = {
    breadcrumbs: [
        { title: "Dashboard", href: dashboard() },
        { title: "Upload Participants", href: "" },
    ],
};
