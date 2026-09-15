import { Button } from "@/components/ui/button";
import {
    Select,
    SelectContent,
    SelectItem,
    SelectTrigger,
    SelectValue,
} from "@/components/ui/select";
import { dashboard } from "@/routes";
import { edit, update } from "@/routes/imports/mapping";
import { Head, useForm } from "@inertiajs/react";
import { FormEventHandler } from "react";

type TargetField = { value: string; label: string; required: boolean };

type MappingProps = {
    import: { id: number; original_filename: string; status: string };
    detectedColumns: string[];
    targetFields: TargetField[];
};

export default function ImportMapping({
    import: importRecord,
    detectedColumns,
    targetFields,
}: MappingProps) {
    const { data, setData, put, processing, errors } = useForm<{
        mapping: Record<string, string>;
    }>({
        mapping: Object.fromEntries(
            detectedColumns.map((column) => [column, ""]),
        ),
    });

    const submit: FormEventHandler = (e) => {
        e.preventDefault();
        put(update.url({ import: importRecord.id }));
    };

    return (
        <>
            <Head title="Map Columns" />
            <div className="flex h-full flex-1 flex-col gap-4 p-4">
                <div className="max-w-xl">
                    <h1 className="text-xl font-semibold">Map Columns</h1>
                    <p className="mt-1 text-sm text-muted-foreground">
                        Match the columns from &quot;
                        {importRecord.original_filename}&quot; to the correct
                        participant fields.
                    </p>

                    <form onSubmit={submit} className="mt-6 space-y-4">
                        {detectedColumns.map((column) => (
                            <div
                                key={column}
                                className="grid grid-cols-2 items-center gap-4"
                            >
                                <span className="text-sm font-medium">
                                    {column}
                                </span>
                                <Select
                                    value={data.mapping[column] || undefined}
                                    onValueChange={(value) =>
                                        setData("mapping", {
                                            ...data.mapping,
                                            [column]: value,
                                        })
                                    }
                                >
                                    <SelectTrigger>
                                        <SelectValue placeholder="Ignore this column" />
                                    </SelectTrigger>
                                    <SelectContent>
                                        {targetFields.map((field) => (
                                            <SelectItem
                                                key={field.value}
                                                value={field.value}
                                            >
                                                {field.label}
                                                {field.required ? " *" : ""}
                                            </SelectItem>
                                        ))}
                                    </SelectContent>
                                </Select>
                            </div>
                        ))}

                        {errors.mapping && (
                            <p className="text-sm text-destructive">
                                {errors.mapping}
                            </p>
                        )}

                        <Button type="submit" disabled={processing}>
                            {processing ? "Saving..." : "Save Mapping"}
                        </Button>
                    </form>
                </div>
            </div>
        </>
    );
}

ImportMapping.layout = {
    breadcrumbs: [
        { title: "Dashboard", href: dashboard() },
        { title: "Upload Participants", href: "/imports/create" },
        { title: "Map Columns", href: "" },
    ],
};
